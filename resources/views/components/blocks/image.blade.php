<section class="mb-5 lg:mb-10">
    <div class="container max-w-[900px] mx-auto">
        <x-image-optimizer::image
            :src="$block->image"
            :width="1000"
            :webp="true"
            :quality="80"
        />
    </div>
</section>
