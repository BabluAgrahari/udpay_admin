# Stock Helper Functions

Simple helper functions for managing product stock (up and down operations).

## Available Functions

### 1. stockUp()
Increase stock for a product or product variant.

**Parameters:**
- `$product_id` (required) - Product ID
- `$stock` (required) - Stock quantity to add
- `$product_variant_id` (optional) - Product variant ID
- `$remarks` (optional) - Remarks for the stock change
- `$user_id` (optional) - User ID (defaults to authenticated user)

**Example:**
```php
// Add 10 units to product stock
$result = stockUp('product_id_123', 10, null, 'Restocked from supplier');

// Add 5 units to variant stock
$result = stockUp('product_id_123', 5, 'variant_id_456', 'Restocked variant');

if ($result['success']) {
    echo "Stock increased successfully. New stock: " . $result['data']['new_stock'];
} else {
    echo "Error: " . $result['message'];
}
```

### 2. stockDown()
Decrease stock for a product or product variant.

**Parameters:**
- `$product_id` (required) - Product ID
- `$stock` (required) - Stock quantity to subtract
- `$product_variant_id` (optional) - Product variant ID
- `$remarks` (optional) - Remarks for the stock change
- `$user_id` (optional) - User ID (defaults to authenticated user)

**Example:**
```php
// Reduce 3 units from product stock
$result = stockDown('product_id_123', 3, null, 'Sold to customer');

// Reduce 2 units from variant stock
$result = stockDown('product_id_123', 2, 'variant_id_456', 'Sold variant');

if ($result['success']) {
    echo "Stock decreased successfully. New stock: " . $result['data']['new_stock'];
} else {
    echo "Error: " . $result['message'];
}
```

## Return Format

Both functions return the same format:

```php
[
    'success' => true/false,
    'message' => 'Success/error message',
    'data' => [
        'previous_stock' => 100,
        'new_stock' => 110,
        'change' => '+10' or '-5',
        'updated_item' => Product/Variant object
    ]
]
```

## Features

- ✅ **Validation**: Prevents negative stock, validates all inputs
- ✅ **Logging**: All stock changes are logged in the `stocks` collection
- ✅ **Flexibility**: Works with both products and variants
- ✅ **Error Handling**: Comprehensive error messages
- ✅ **Security**: Requires authentication and proper permissions
- ✅ **Audit Trail**: Complete history of all stock movements

## Error Messages

Common error messages:
- "Product ID is required"
- "Stock must be a positive number"
- "Product not found"
- "Product variant not found"
- "Insufficient stock. Cannot reduce stock below 0"
- "User ID is required"

## Usage in Controllers

```php
public function updateStock(Request $request)
{
    $validator = \Validator::make($request->all(), [
        'product_id' => 'required|exists:products,_id',
        'stock' => 'required|integer|min:1',
        'type' => 'required|in:up,down',
        'product_variant_id' => 'nullable|exists:product_variants,_id',
        'remarks' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
        return $this->failMsg($validator->errors()->first());
    }

    if ($request->type === 'up') {
        $result = stockUp(
            $request->product_id,
            $request->stock,
            $request->product_variant_id,
            $request->remarks
        );
    } else {
        $result = stockDown(
            $request->product_id,
            $request->stock,
            $request->product_variant_id,
            $request->remarks
        );
    }

    if ($result['success']) {
        return $this->successMsg($result['message'], $result['data']);
    } else {
        return $this->failMsg($result['message']);
    }
}
``` 