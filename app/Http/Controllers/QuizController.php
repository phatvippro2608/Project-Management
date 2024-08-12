<?php

namespace App\Http\Controllers;

use App\Models\CreateQuizModel;
use App\Models\QuizModel;
use App\Models\SpreadsheetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class QuizController extends Controller
{
    function getView()
    {
        $model = new QuizModel();
//        dd($model->getInfo());
        return view('auth.quiz.quiz',
            ['data' => $model->getInfo()]);
    }

    function getViewQuestionBank()
    {
        $model = new QuizModel();
        return view('auth.quiz.question-bank', ['courses' => $model->getCourse()]);
    }

    function addQuestion(Request $request)
    {
//        dd($request);
        $validated = $request->validate([
            'add_question' => 'required|string',
            'add_answer_a' => 'required|string',
            'add_answer_b' => 'required|string',
            'add_answer_c' => 'required|string',
            'add_answer_d' => 'required|string',
            'add_correct_answer' => 'required|string',
            'add_course_name' => 'required|int'
        ]);

        $imagePath = null;
        if ($request->hasFile('add_question_img')) {
            $file = $request->file('add_question_img');
            $imagePath = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('question_bank_image/'), $imagePath);
        }

        QuizModel::create([
            'question' => $validated['add_question'],
            'question_image' => $imagePath,
            'question_a' => $validated['add_answer_a'],
            'question_b' => $validated['add_answer_b'],
            'question_c' => $validated['add_answer_c'],
            'question_d' => $validated['add_answer_d'],
            'correct' => $validated['add_correct_answer'],
            'course_id' => $validated['add_course_name']
        ]);

        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Question added successfully',
        ]);
    }

    function getQuestionList($id)
    {
        $question_list = DB::table('question_bank')
            ->join('courses', 'question_bank.course_id', '=', 'courses.course_id')
            ->where('question_bank.course_id', $id)
            ->get();
        return response()->json([
            'question_list' => $question_list,
        ]);
    }

    public function destroy($id)
    {
//        dd($id);
        $question = QuizModel::findOrFail($id);

        // Define the directory path
        $directoryPath = public_path('question_bank_image/');

        // Check if the question_image is not null
        if (!is_null($question->question_image)) {
            // Define the file path
            $filePath = $directoryPath . $question->question_image;

            // Check if the file exists before attempting to delete it
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $question->delete();
        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    }

    public function edit($id)
    {
        $question = QuizModel::findOrFail($id);

        return response()->json([
            'question' => $question,
        ]);
    }

    function update(Request $request, $id)
    {
//        dd($request, $id);
        $validated = $request->validate([
            'question' => 'string',
            'question_a' => 'string',
            'question_b' => 'string',
            'question_c' => 'string',
            'question_d' => 'string',
            'correct' => 'string',
            'course_id' => 'int'
        ]);

        $model = new QuizModel();
        $imgOld = $model->getQuestionImg($id);

        $imagePath = $imgOld;
//        dd($request->hasFile('question_image'));
        if ($request->hasFile('question_image')) {
            $file = $request->file('question_image');
            $imagePath = time() . '_' . $file->getClientOriginalName();
            // Move the uploaded file to the desired location
            $file->move(public_path('question_bank_image/'), $imagePath);
        }


        // Find the question by ID and update the data
        $question = QuizModel::findOrFail($id);
        $question->update(array_merge($validated, ['question_image' => $imagePath]));

        // Return a JSON response indicating success
        return response()->json([
            'success' => true,
            'question' => $question,
        ]);
    }

    public function import(Request $request, $id)
    {
        try {
            $dataExcel = SpreadsheetModel::readExcel($request->file('file-excel'));
            $num_row = 0;
            $dataArray = array();
//            dd($dataExcel['data']);
            foreach ($dataExcel['data'] as $item) {
                $num_row++;
                if ($num_row == 1) {
                    continue; // Skip header row
                }

                $data = [
                    'question' => trim($item[1]),
                    'question_a' => trim($item[2]),
                    'question_b' => trim($item[3]),
                    'question_c' => trim($item[4]),
                    'question_d' => trim($item[5]),
                    'correct' => trim($item[6]),
                    'course_id' => $id,
                ];

                $dataArray[] = $data;
            }

            if (!empty($dataArray)) {
                foreach ($dataArray as $data) {
                    QuizModel::create($data);
                }
                return response()->json(['status' => 200, 'message' => 'Import successfully']);
            } else {
                return response()->json(['status' => 400, 'message' => 'No data for import']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'An error occurred ' . $e->getMessage()]);
        }

    }

    public function export($id)
    {
        $inputFileName = public_path('excel-example/question-bank-export.xlsx');
        $inputFileType = IOFactory::identify($inputFileName);
        $objReader = IOFactory::createReader($inputFileType);
        $excel = $objReader->load($inputFileName);

        $excel->setActiveSheetIndex(0);
        $excel->getDefaultStyle()->getFont()->setName('Times New Roman');

        $stt = 1;
        $cell = $excel->getActiveSheet();
        $question_bank = DB::table('question_bank')->where('course_id', $id)->get();
        $num_row = 2;
        foreach ($question_bank as $row) {
            $cell->setCellValue('A' . $num_row, $stt++);
            $cell->setCellValue('B' . $num_row, $row->question);
            $cell->setCellValue('C' . $num_row, $row->question_a);
            $cell->setCellValue('D' . $num_row, $row->question_b);
            $cell->setCellValue('E' . $num_row, $row->question_c);
            $cell->setCellValue('F' . $num_row, $row->question_d);
            $cell->setCellValue('G' . $num_row, $row->correct);
            $borderStyle = $cell->getStyle('A' . $num_row . ':G' . $num_row)->getBorders();
            $borderStyle->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $cell->getStyle('A' . $num_row . ':G' . $num_row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $num_row++;
        }
        foreach (range('A', 'G') as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $fileName = "Question-Bank-Export-" . time() . ".xlsx";
        $filePath = public_path('excel-download/' . $fileName);

        $writer = IOFactory::createWriter($excel, 'Xlsx');
        $writer->save($filePath);

        return response()->json(['success' => true, 'status' => 200, 'message' => 'Export successfully', 'file' => 'excel-download/' . $fileName]);
    }

    public function deleteExportedFile(Request $request)
    {
        $filePath = public_path($request->input('file'));
        if (file_exists($filePath)) {
            unlink($filePath);
            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'File not found']);
    }

}
