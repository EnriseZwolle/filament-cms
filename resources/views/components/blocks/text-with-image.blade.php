<section class="mb-5 lg:mb-10">
    <div class="container max-w-container">
        <div class="flex gap-5 w-full @if($block->position === 'right') flex-col md:flex-row-reverse @else flex-col md:flex-row @endif">
            <div class="w-full md:w-1/2">
                <figure class="w-full">
                    <x-image-optimizer::image
                        :src="$block->image"
                        :width="900"
                        :webp="true"
                        :quality="70"
                    />
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
