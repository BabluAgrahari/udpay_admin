# jQuery Quantity Control Plugin

A reusable jQuery plugin for quantity controls with add/remove functionality, perfect for e-commerce applications.

## Features

- ✅ Increment/Decrement buttons
- ✅ Input validation
- ✅ Keyboard support (Arrow keys)
- ✅ Min/Max quantity limits
- ✅ Visual feedback
- ✅ Responsive design
- ✅ Callback functions
- ✅ Public methods for external control

## Installation

1. Include jQuery in your project
2. Include the quantity control plugin:

```html
<script src="path/to/quantity-control.js"></script>
```

## Basic Usage

### HTML Structure

```html
<div class="quantity">
    <button class="qty-btn">-</button>
    <input type="text" value="1">
    <button class="qty-btn">+</button>
</div>
```

### JavaScript Initialization

```javascript
// Auto-initialize all quantity controls
$(document).ready(function() {
    $('.quantity').quantityControl();
});

// Or with custom options
$('.quantity').quantityControl({
    minQuantity: 1,
    maxQuantity: 10,
    step: 1,
    onQuantityChange: function(newValue, oldValue) {
        console.log('Quantity changed from', oldValue, 'to', newValue);
    }
});
```

## Configuration Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `minQuantity` | Number | 1 | Minimum allowed quantity |
| `maxQuantity` | Number | 999 | Maximum allowed quantity |
| `step` | Number | 1 | Increment/decrement step |
| `allowZero` | Boolean | false | Allow zero quantity |
| `onQuantityChange` | Function | null | Callback when quantity changes |
| `onMaxReached` | Function | null | Callback when max quantity reached |
| `onMinReached` | Function | null | Callback when min quantity reached |
| `buttonClass` | String | 'qty-btn' | CSS class for buttons |
| `inputClass` | String | 'qty-input' | CSS class for input |
| `disabledClass` | String | 'disabled' | CSS class for disabled state |

## Public Methods

### Get Current Value
```javascript
var quantity = $('.quantity').data('quantityControl').getValue();
```

### Set Value
```javascript
$('.quantity').data('quantityControl').setValue(5);
```

### Increase/Decrease
```javascript
$('.quantity').data('quantityControl').increase();
$('.quantity').data('quantityControl').decrease();
```

### Enable/Disable
```javascript
$('.quantity').data('quantityControl').enable();
$('.quantity').data('quantityControl').disable();
```

### Destroy Plugin
```javascript
$('.quantity').data('quantityControl').destroy();
```

## Examples

### Basic E-commerce Product Page

```html
<div class="product-details">
    <div class="quantity">
        <button class="qty-btn">-</button>
        <input type="text" value="1">
        <button class="qty-btn">+</button>
    </div>
    <button class="add-to-cart" data-product-id="123">Add to Cart</button>
</div>

<script>
$(document).ready(function() {
    $('.quantity').quantityControl({
        minQuantity: 1,
        maxQuantity: 10,
        onQuantityChange: function(newValue, oldValue) {
            console.log('Quantity updated:', newValue);
        }
    });
    
    $('.add-to-cart').on('click', function() {
        var productId = $(this).data('product-id');
        var quantity = $('.quantity').data('quantityControl').getValue();
        
        // Add to cart logic
        addToCart(productId, quantity);
    });
});
</script>
```

### Shopping Cart with Multiple Items

```html
<div class="cart-item" data-item-id="1">
    <div class="quantity">
        <button class="qty-btn">-</button>
        <input type="text" value="2">
        <button class="qty-btn">+</button>
    </div>
</div>

<script>
$('.cart-item .quantity').each(function() {
    $(this).quantityControl({
        minQuantity: 1,
        maxQuantity: 99,
        onQuantityChange: function(newValue, oldValue) {
            var itemId = $(this).closest('.cart-item').data('item-id');
            updateCartItem(itemId, newValue);
        }
    });
});
</script>
```

### Advanced Configuration

```javascript
$('.quantity').quantityControl({
    minQuantity: 0,
    maxQuantity: 50,
    step: 5,
    allowZero: true,
    onQuantityChange: function(newValue, oldValue) {
        updateTotal(newValue);
        updateStock(newValue);
    },
    onMaxReached: function(value) {
        showMessage('Maximum quantity reached');
    },
    onMinReached: function(value) {
        showMessage('Minimum quantity reached');
    }
});
```

## CSS Styling

The plugin includes basic CSS styles, but you can customize them:

```css
.quantity {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: 2px solid #e0e0e0;
    background: #fff;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.qty-btn:hover:not(:disabled) {
    border-color: #007bff;
    background: #f8f9fa;
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.quantity input[type="text"] {
    width: 60px;
    height: 40px;
    text-align: center;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
}
```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## License

This plugin is open source and available under the MIT License.

## Contributing

Feel free to submit issues and enhancement requests! 