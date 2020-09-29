<tr class="template-download fade <?php echo $row;?>">
    <td width="3%" class="preview">
        <input type="radio" name="primaryimage" value="<?php echo $image->filename;?>"  <?php echo $image->primary?'checked':'';?>>
    </td>
    <td width="25%" class="preview">
            <a href="<?php echo $image->url;?>" title="<?php echo $image->name;?>" rel="gallery" download="<?php echo $image->name;?>">
                <img src="<?php echo $image->thumbnail_url;?>" style="vertical-align:middle;width:100px;height:100px">
            </a>
    </td>
    <td width="70%" class="name">
            <div class="wordwrap" style="width:90px;padding-left:10px;font-size:0.9em;">
                <a href="<?php echo $image->url;?>" title="<?php echo $image->name;?>" rel="<?php echo $image->name;?>&&'gallery'" download="<?php echo $image->name;?>"><?php echo $image->name;?></a>
                <br><br><?php echo Helper::formatBytes($image->size);?>
            </div>
    </td>
    <td width="5%" class="delete">
        <button style="padding:3px 2px;border:0;background:inherit;" class="btn btn-danger" data-type="<?php echo $image->delete_type;?>" data-url="<?php echo $image->delete_url;?>">
            <i class="icon-trash icon-white"></i>
            <i style="cursor:pointer" class="fa fa-times"></i>    
            <?php //echo Chtml::image($this->getAssetsUrl('common.assets.images').'/delete.png');?>
        </button>
    </td>         
</tr>