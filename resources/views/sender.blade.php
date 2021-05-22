<form action="/sender" method="post">
    {{csrf_field()}}
    <input type="text" name="text"/>
    <button type="submit">send</button>

</form>
