<h1>Редактировать товар</h1>

<form action="/products/{{ $product->id }}" method="POST">
    @csrf
    @method('PUT') <div>
        <label>Название:</label>
        <input type="text" name="name" value="{{ $product->name }}" required>
    </div>
    
    <div style="margin-top: 10px;">
        <label>Цена:</label>
        <input type="number" name="price" step="0.01" value="{{ $product->price }}" required>
    </div>
    
    <div style="margin-top: 15px;">
        <button type="submit">Сохранить изменения</button>
        <a href="/">Отмена</a>
    </div>
</form>