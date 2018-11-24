<div class="col-md-2 deleteBox">
    <a href="{{ asset($attachment->media->folder.$attachment->media->filename) }}"
       target="_blank">
        <img height="60" src="{{ asset($attachment->media->folder.'100X100'.$attachment->media->filename) }}" alt="avatar" class="img-thumbnail" >
    </a>

    <a class="btn btn-xs btn-danger ajaxdelete"
       data-id="<?php echo $attachment->id; ?>"
       data-url="<?php echo url('webpanel/attachments/delete/' . encrypt($attachment->id)); ?>"
       data-token="<?php echo urlencode(md5($attachment->id)); ?>"><span
            class="fa fa-trash"></span> </a>

</div>