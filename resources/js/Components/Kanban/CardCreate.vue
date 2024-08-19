<script setup>
import { ref, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { PlusIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    column: Object,
    uniqueId: String,  // Unique identifier for the modal
});

const emit = defineEmits(['created']);

const form = useForm({
    content: '',
});

const inputCardContentRef = ref();
const isCreating = ref(false);

const onSubmit = () => {
    form.post(route('columns.cards.store', { column: props?.column?.id }), {
        onSuccess: () => {
            form.reset();
            isCreating.value = false;
            emit('created');
        },
    });
};
const followers = ref([
    { id: 1, selected: true },
    { id: 2, selected: true },
    { id: 3, selected: true },
    { id: 5, selected: true },
    { id: 6, selected: true }
]);

const showAddTask = () => {
    // Ensure modal is correctly identified using the uniqueId
    const modalId = `#${props.uniqueId}`;
    $(modalId).modal('show');
};
const toggleSelection = (id) => {
    const index = followers.value.findIndex(f => f.id === id);
    if (index !== -1) {
        followers.value[index].selected = !followers.value[index].selected;
    }
};
</script>

<template>
    <div :id="uniqueId" class="modal fade modal-xl" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                    <div class="row pt-2 pb-2 ps-3 pe-3 bg-primary text-white">
                        <div class="col-md-12 w-100 d-flex align-items-center justify-content-between">
                            <label class="fs-5 fw-bold">Add Task</label>
                            <i class="bi bi-x-lg fs-5" style="cursor:pointer" data-bs-dismiss="modal" aria-label="Close"></i>
                        </div>
                    </div>
                    <hr>
                    <div class="task-content p-3">
                        <div class="row">
                            <div class="col-md-12">
                                <label style="font-size: 14px">Task name (Required)</label>
                                <textarea rows="1" type="text" style="font-size: 14px" class=" form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label style="font-size: 14px">Description</label>
                                <textarea rows="3" type="text" style="font-size: 14px" class="form-control bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="" style="font-size: 14px">Task Group</label>
                                <select rows="3" type="text" style="font-size: 14px" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-1 val-team-name">
                                    <option class="fs-5">Lua chon 1</option>
                                </select>

                                <label class=" mt-3" style="font-size: 14px">Employee</label>
                                <select rows="3" type="text" style="font-size: 14px" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-1 val-team-name">
                                    <option class="fs-5">Lua chon 1</option>
                                </select>

                                <label class="mt-3" style="font-size: 14px">Start date</label>
                                <input  type="date" style="font-size: 14px" class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-1 val-team-name"/>

                                <label class=" mt-3" style="font-size: 14px">End date</label>
                                <input  type="date" style="font-size: 14px" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-1 val-team-name"/>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <label  style="font-size: 14px">Follower</label>
                                </div>
                                <div class="row g-1">
                                    <div v-for="follower in followers" :key="follower.id" class="col-md-4 p-1">
                                        <div class="shadow-md p-2 position-relative border-1 rounded-2" @click="toggleSelection(follower.id)">
                                            <div class="w-100 d-flex">
                                                <img src="/assets/img/1722326801.png" class="rounded-circle me-2" style="width: 40px!important; height: 40px!important;"/>
                                                <div class="d-flex flex-column">
                                                    <div class="employee-name" style="font-size: 14px">
                                                        Nguyen Van A
                                                    </div>
                                                    <div class="employee-name text-muted" style="font-size: 12px">
                                                        Admin
                                                    </div>
                                                </div>
                                                <div class="triangle" :class="{ selected: follower.selected }">
                                                    <i class="bi bi-check-lg"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row mt-3 d-flex justify-content-end">
                            <button type="button" class="w-auto btn btn-danger" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="w-auto btn btn-primary ms-2 me-3">Create</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <button
        v-if="!isCreating"
        @click.prevent="showAddTask"
        type="button"
        class="flex items-center p-2 text-sm rounded-md font-medium bg-gray-200 text-gray-600 hover:text-black hover:bg-gray-300 w-full"
    >
        <PlusIcon class="w-5 h-5"/>
        <span class="ml-1">Add task</span>
    </button>
</template>
<style>
.triangle {
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border-left: 34px solid transparent; /* Adjust the size of the triangle */
    border-top: 34px solid red; /* Adjust the size and color of the triangle */
    border-radius: 0 5px 0 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
.triangle i {
    color: white; /* Adjust the color of the icon */
    font-size: 16px;
    font-weight: bolder;
    position: absolute;
    top: -33px;
    right: 2px;
    z-index: 100;
}
.triangle.selected {
    display: flex;
}
.triangle:not(.selected) {
    display: none;
}
</style>
