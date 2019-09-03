@error($fieldName)
    <p style="color: red;" >{{$errors->first($fieldName)}}</p>
@enderror