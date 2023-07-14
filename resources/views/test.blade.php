<form action="/test" method="post" enctype="multipart/form-data">
    @csrf()
    <input type="date" name='timeout'>
    <button type="submit">Upload</button>
</form>