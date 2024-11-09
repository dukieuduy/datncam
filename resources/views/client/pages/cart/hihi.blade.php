@for ($i = 1; $i <= 10; $i++)
    <!-- Form for Logged-in User (with user_id) -->
    <form action="{{ route('cart.add') }}" method="POST" style="margin-bottom: 10px;">
        @csrf
        <input type="hidden" name="product_id" value="{{ $i }}"> <!-- Dynamic product_id -->
        <input type="hidden" name="quantity" value="1"> <!-- Hardcoded quantity -->
        <input type="hidden" name="user_id" value="{{ auth()->id() }}"> <!-- Only for logged-in users -->
        <button type="submit">Add to Cart (User) - Product {{ $i }}</button>
    </form>

    <!-- Form for Guest User (Session-based) -->
    <form action="{{ route('cart.add') }}" method="POST" style="margin-bottom: 10px;">
        @csrf
        <input type="hidden" name="product_id" value="{{ $i }}"> <!-- Dynamic product_id -->
        <input type="hidden" name="quantity" value="1"> <!-- Hardcoded quantity -->
        <!-- No user_id included -->
        <button type="submit">Add to Cart (Session) - Product {{ $i }}</button>
    </form>
@endfor
