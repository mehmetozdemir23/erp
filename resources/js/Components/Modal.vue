<template>
    <div tabindex="-1" aria-hidden="true"
        class="overflow-hidden fixed top-0 bottom-0 right-0 left-0 z-40 flex justify-center items-center w-full md:inset-0 md:h-full bg-gray-800 bg-opacity-50">
        <div class="relative p-4 w-full max-w-2xl h-full">
            <div class="relative bg-white rounded-lg h-full shadow overflow-hidden">
                <slot/>
            </div>
        </div>
    </div>
</template>