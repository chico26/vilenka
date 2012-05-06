<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if (isset($error)) echo $error;?>
<?php echo form_open_multipart('archivos/do_upload/'.$parent_directory_id);?>
<input type="hidden" name="directory_id" value="<?php echo $parent_directory_id ?>" />
<input type="file" name="userfile[]" size="20" class="multi" />
<input type="submit" value="Guardar" />
</form>
<script type="text/javascript" src="/js/jquery.MultiFile.min.js"></script>
<script type="text/javascript" src="/js/jquery-latest.js"></script>
