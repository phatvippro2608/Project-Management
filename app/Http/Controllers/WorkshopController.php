<?php

namespace App\Http\Controllers;

use App\Models\WorkshopModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function index()
    {
        $workshops = WorkshopModel::all();
        return view('auth.lms.workshop.index', ['workshops' => $workshops]);
    }

    public function show($workshop_id)
    {
        $workshop = WorkshopModel::findOrFail($workshop_id);
        return view('auth.lms.workshop.show', compact('workshop'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'workshop_title' => 'required|string|max:255',
            'workshop_description' => 'required|string|max:1000',
            'workshop_image_url' => 'required|string|max:255',
            'workshop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $workshop = new WorkshopModel();
        $workshop->workshop_title = $request->input('workshop_title');
        $workshop->workshop_description = $request->input('workshop_description');
        $workshop->workshop_image_url = $request->input('workshop_image_url');

        if ($request->hasFile('workshop_image')) {
            $image = $request->file('workshop_image');
            $name = Str::slug($request->input('workshop_title')) . '_' . time();
            $folder = '/uploads/images/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $workshop->workshop_image = $filePath;
        }

        $workshop->save();

        return redirect()->route('workshop.show', ['workshop_id' => $workshop->workshop_id])->with('success', 'Workshop added successfully');
    }

    public function update(Request $request, $workshop_id)
    {
        $request->validate([
            'workshop_title' => 'required|string|max:255',
            'workshop_description' => 'required|string|max:1000',
            'workshop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $workshop = WorkshopModel::findOrFail($workshop_id);
        $workshop->workshop_title = $request->input('workshop_title');
        $workshop->workshop_description = $request->input('workshop_description');

        if ($request->hasFile('workshop_image')) {
            $image = $request->file('workshop_image');
            $name = Str::slug($request->input('workshop_title')) . '_' . time();
            $folder = '/uploads/images/';
            $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();
            $this->uploadOne($image, $folder, 'public', $name);
            $workshop->image_url = $filePath;
        }

        $workshop->save();

        return redirect()->route('workshop.show', ['workshop_id' => $workshop->workshop_id])->with('success', 'Workshop updated successfully');
    }

    public function uploadOne($image, $folder, $disk, $name)
    {
        $image->storeAs($folder, $name.'.'.$image->getClientOriginalExtension(), $disk);
    }

    public function live($workshop_id)
    {
        $workshop = WorkshopModel::findOrFail($workshop_id);

        if($workshop->live_technology == 'youtube'){
            // Parse youtube_video_id từ live_url
            $url = $workshop->live_url;
            parse_str(parse_url($url, PHP_URL_QUERY), $query);
            $youtube_video_id = $query['v'];
            // Thêm youtube_video_id vào đối tượng workshop để truyền vào view
            $workshop->youtube_video_id = $youtube_video_id;
            return view('auth.lms.live', ['workshop' => $workshop, 'youtube_video_id' => $youtube_video_id]);
        } else if($workshop->live_technology == 'zoom')
            return redirect()->away('https://www.google.com/search?q=zoom');
        else if($workshop->live_technology == 'team')
            return redirect()->away('https://www.google.com/search?q=team');
        else
            return redirect()->away('https://www.google.com/');
    }

    public function chats()
    {
        return $this->hasMany(WorkshopChatController::class);
    }
}
