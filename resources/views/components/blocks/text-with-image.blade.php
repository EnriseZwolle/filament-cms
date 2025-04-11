<section class="mb-5 lg:mb-10">
    <div class="container max-w-container">
        <div class="flex gap-5 w-full @if($block->position === 'right') flex-col md:flex-row-reverse @else flex-col md:flex-row @endif">
            <div class="w-full md:w-1/2">
                <figure class="w-full">
                    <x-curator-glider :media="(int)$block->image" class="relative w-full" :width="900" />
                </figure>
            </div>
            <div class="w-full md:w-1/2">
                <div class="editor">
                    {!! $block->text !!}
                </div>
            </div>
        </div>
    </div>
</section>
