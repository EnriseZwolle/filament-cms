<div class="{{ $type }} w-full max-w-[768px] px-5 mx-auto mb-10 lg:mb-16">
    <div
            x-cloak
            x-data="{ open: false }"
            class="flex flex-col w-full p-5 border border-gray-200 hover:border-gray-300 rounded-md lg:rounded-lg transition-colors duration-150 ease-in-out"
    >
        <h2 class="flex flex-col w-full">
            <button
                    @click="open = ! open"
                    :aria-expanded="open ? 'true' : 'false'"
                    class="font-family font-bold text-lg lg:text-xl flex flex-row justify-between text-left"
            >
                {{ $block->title }}

                <div
                        class="transition-transform duration-150 ease-in-out"
                        :class="{'rotate-0': open, 'rotate-45': ! open}"
                >
                    <x-svg
                            class="svg-icon relative inline-flex self-center h-5 w-5 text-secondary"
                            src="assets/svg/close"
                    />
                </div>
            </button>
        </h2>

        <div
                x-show="open"
                class="editor mt-5"
        >
            <x-blocks :blocks="$block->content" />
        </div>
    </div>
</div>
