<section class="mb-5 lg:mb-10">
    <div class="container max-w-container-medium">
        <x-image-optimizer::image
            :src="$block->image"
            :width="900"
            :webp="true"
            :quality="70"
        />
    </div>
</section>
