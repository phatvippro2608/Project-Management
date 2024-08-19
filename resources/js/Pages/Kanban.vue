<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Column from '@/Components/Kanban/Column.vue';
import ColumnCreate from '@/Components/Kanban/ColumnCreate.vue';

const props = defineProps({
    board: Object,
});

const columns = computed(() => props.board?.data?.columns);
const boardTitle = computed(() => props.board?.data?.title);

const columnsWithOrder = ref([]);

const onReorderChange = column => {
    columnsWithOrder.value?.push(column);
};

const onReorderCommit = () => {
    if (!columnsWithOrder?.value?.length) {
        return;
    }

    router.put(route('cards.reorder'), {
        columns: columnsWithOrder.value,
    });
};

const handleWheel = (event) => {
    const container = event.currentTarget;
    const rect = container.getBoundingClientRect();
    const buffer = 50; // Khoảng cách từ cạnh dưới để bắt đầu cuộn
    const mouseY = event.clientY;

    // Kiểm tra nếu chuột gần cạnh dưới
    if (mouseY >= rect.bottom - buffer) {
        // Cuộn ngang dựa trên sự kiện cuộn chuột
        container.scrollBy({
            left: event.deltaY, // Cuộn theo hướng dọc
            behavior: 'smooth'
        });
        event.preventDefault(); // Ngăn chặn hành vi cuộn mặc định
    }
};

onMounted(() => {
    const container = document.querySelector('.scroll-container');
    if (container) {
        container.addEventListener('wheel', handleWheel);
    }
});

onUnmounted(() => {
    const container = document.querySelector('.scroll-container');
    if (container) {
        container.removeEventListener('wheel', handleWheel);
    }
});
</script>

<template>
    <Head>
        <title>Kanban Board</title>
    </Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-black text-2xl text-gray-800 leading-tight">
                {{ boardTitle }}
            </h2>
        </template>

        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <div class="flex-1 h-full overflow-x-auto scroll-container custom-scrollbar">
                <div
                    class="inline-flex h-full items-start p-4 space-x-4 overflow-hidden"
                >
                    <Column
                        v-for="column in columns"
                        :key="column.title"
                        :column="column"
                        @reorder-change="onReorderChange"
                        @reorder-commit="onReorderCommit"
                    />
                    <div class="w-72">
                        <ColumnCreate :board="board.data" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Thanh cuộn ngang */
.custom-scrollbar::-webkit-scrollbar {
    height: 8px; /* Đặt chiều cao của thanh cuộn ngang */
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #888; /* Màu của thanh cuộn */
    border-radius: 10px; /* Bo tròn góc của thanh cuộn */
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #555; /* Màu của thanh cuộn khi di chuột qua */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1; /* Màu nền của thanh cuộn */
}
</style>
