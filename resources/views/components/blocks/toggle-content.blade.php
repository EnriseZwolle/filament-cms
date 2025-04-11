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
                    <span class="svg-icon relative inline-flex self-center h-5 w-5 text-secondary">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.3 5.71c-.39-.39-1.02-.39-1.41 0L12 10.59 7.11 5.7c-.39-.39-1.02-.39-1.41 0-.39.39-.39 1.02 0 1.41L10.59 12 5.7 16.89c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0L12 13.41l4.89 4.89c.39.39 1.02.39 1.41 0 .39-.39.39-1.02 0-1.41L13.41 12l4.89-4.89c.38-.38.38-1.02 0-1.4z"/></svg>
                    </span>
                </div>
            </button>
        </h2>

        <div x-show="open" class="editor mt-5">
            <x-filament-cms::blocks :blocks="$block->content" />
        </div>
    </div>
</div>
