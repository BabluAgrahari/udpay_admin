# Product Details Module

This module allows you to manage detailed product information including ingredients, uses, and results for each product.

## Features

- **Rich Text Editing**: Full-featured CKEditor 5 integration for all text fields
- **AJAX Operations**: All operations performed via AJAX for smooth user experience
- **Auto-save**: Optional auto-save functionality (30-second delay)
- **Status Management**: Enable/disable product details
- **Responsive Design**: Works on all device sizes

## Database Structure

### Table: `uni_product_details`

| Field | Type | Description |
|-------|------|-------------|
| `id` | bigint | Primary key |
| `product_id` | bigint | Foreign key to products table |
| `details` | longtext | Product details/description |
| `key_ings` | longtext | Key ingredients information |
| `uses` | longtext | Product uses/benefits |
| `result` | longtext | Expected results/outcomes |
| `status` | enum('0','1') | Active/Inactive status |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Last update timestamp |

## Routes

- `GET /crm/products/{id}/details` - View product details page
- `POST /crm/products/store-detail` - Save/update product details
- `GET /crm/products/{id}/detail` - Get details data (API)

## Usage

1. **Access Details Page**: Click the "Details" button (📋 icon) in the products list
2. **Edit Content**: Use the rich text editors to modify content
3. **Save Changes**: Click "Save Details" to persist changes
4. **Reset**: Use "Reset" button to revert to original values

## CKEditor Features

The rich text editors include:
- Text formatting (bold, italic, underline, strikethrough)
- Headings and paragraphs
- Lists (bulleted and numbered)
- Links and media embedding
- Tables
- Font size and color options
- Text alignment
- Indentation controls
- Image handling
- Block quotes
- Page breaks

## Technical Implementation

### Models
- `ProductDetail` - Handles product detail data
- `Product` - Updated with details relationship

### Controller Methods
- `details($id)` - Display details page
- `storeDetail(Request $request)` - Save/update details
- `getDetail($productId)` - API endpoint for details data

### JavaScript Features
- CKEditor initialization and management
- AJAX form submission
- Loading states and error handling
- Auto-save functionality
- Form reset capabilities

## Installation

1. Run the migration to create the table:
   ```bash
   php artisan migrate
   ```

2. The module is ready to use - no additional configuration required.

## Permissions

The module requires the `product` permission to access all functionality.

## Notes

- All text fields support HTML content via CKEditor 5
- Data is automatically validated on the server side
- Foreign key constraints ensure data integrity
- Cascade delete removes details when product is deleted
- Uses free, open-source CKEditor 5 