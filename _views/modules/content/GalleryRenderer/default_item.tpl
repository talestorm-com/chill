<div class="GalleryRendererItem" data-image="{$image->image}" data-context="{$image->context}" data-title="{$image->title}" data-owner="{$image->owner_id}">    
    <div class="GalleryRendererItemInner ">                                        
        <div class="GalleryRendererItemImage">            
            <img src="/media/{$image->context}/{$image->owner_id}/{$image->image}.{$image_specification}.jpg" alt="{$image->title}" />            
        </div>            
        <div class="GalleryRendererItemTitle">{$image->title}</div>            
    </div>
</div>