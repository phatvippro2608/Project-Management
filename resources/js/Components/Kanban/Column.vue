<script setup>
import {computed, nextTick, ref, watch} from 'vue';
import {router} from '@inertiajs/vue3';
import cloneDeep from 'lodash.clonedeep';
import Draggable from 'vuedraggable';
import Card from '@/Components/Kanban/Card.vue';
import CardCreate from '@/Components/Kanban/CardCreate.vue';
import ConfirmDialog from '@/Components/Kanban/ConfirmDialog.vue';
import MenuDropDown from '@/Components/Kanban/MenuDropDown.vue';

const props = defineProps({
    board: Number,
    column: Object,
});

const emit = defineEmits(['reorder-commit', 'reorder-change']);

const columnTitle = computed(() => props.column?.title);
const cards = ref(props?.column?.cards);
const latestCards = computed(() => props?.column?.cards);
const columnId = computed(() => props?.column?.id);
const cardsRef = ref();

// Keep the cards up-to-date
watch(
    () => props?.column,
    () => (cards.value = props?.column?.cards)
);

// TODO: Move to composable useModal
const isOpen = ref(false);
const closeModal = confirm => {
    isOpen.value = false;
    if (confirm) {
        router.delete(route('columns.destroy', {column: props?.column?.id}));
    }
};
const openModal = () => (isOpen.value = true);

const onCardCreated = async () => {
    await nextTick();
    cardsRef.value.scrollTop = cardsRef.value.scrollHeight;
};

const menuItems = [
    {
        name: 'Delete column',
        action: () => openModal(),
    },
];

const onReorderCards = () => {
    const cloned = cloneDeep(cards?.value);

    const cardsWithOrder = [
        ...cloned?.map((card, index) => ({
            id: card.id,
            position: index * 1000 + 1000,
        })),
    ];

    emit('reorder-change', {
        id: props?.column?.id,
        cards: cardsWithOrder,
    });
};
const handleClick = (event) => {
    openModal();

};
const onReorderEnds = () => {
    emit('reorder-commit');
};

</script>

<template>
    <div class="w-72 max-h-full bg-[#EBECF0] flex flex-col rounded-md">
        <div class="flex items-center justify-between px-3 py-2">
            <h3 class="font-semibold text-sm text-gray-700">
                {{ columnTitle }}
            </h3>
            <MenuDropDown :menuItems="menuItems"/>
        </div>
        <div class="pb-2 flex-1 flex flex-col overflow-hidden">
            <div class="px-3 py-1 h-100 overflow-y-auto custom-scrollbar" ref="cardsRef">
                <Draggable
                    v-model="cards"
                    group="cards"
                    item-key="id"
                    tag="ul"
                    drag-class="drag"
                    ghost-class="ghost"
                    class="space-y-3"
                    @change="onReorderCards"
                    @end="onReorderEnds"
                >
                    <template #item="{ element }">
                        <li
                            @click="handleClick"
                        >
                            <Card :card="element"/>
                        </li>
                    </template>
                </Draggable>
            </div>
            <div class="px-3 mt-2">
                <CardCreate :column="column" :uniqueId="String(props?.column?.id)" @created="onCardCreated"/>
            </div>
        </div>
    </div>
    <ConfirmDialog
        :show="isOpen"
        @confirm="closeModal($event)"
        title="Remove Column"
        message="Are you sure you want to delete this column and all its cards?"
    />
</template>
<style>
/* For WebKit-based browsers (Chrome, Safari) */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px; /* Width of the scrollbar */
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1; /* Background color of the track */
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #888; /* Color of the scroll thumb */
    border-radius: 10px; /* Rounded corners of the thumb */
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #555; /* Darker color of the thumb on hover */
}

/* For Firefox */
.custom-scrollbar {
    scrollbar-width: thin; /* Set scrollbar width to thin */
    scrollbar-color: #888 #f1f1f1; /* Thumb color and track color */
}
</style>
