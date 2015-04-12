<script type="text/javascript" src="/includes/ckeditor/ckeditor.js"></script>
<textarea id="[name]" name="[name]" style="width:900px">[value]</textarea>
<script type="text/javascript">
CKEDITOR.replace( '[name]', {
filebrowserBrowseUrl : '/includes/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '/includes/ckfinder/ckfinder.htm?Type=Images',
filebrowserFlashBrowseUrl : '/includes/ckfinder/ckfinder.htm?Type=Flash',
filebrowserUploadUrl : '/includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '/includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
filebrowserFlashUploadUrl : '/includes/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
} );
</script>