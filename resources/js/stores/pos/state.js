export default function () {
	return {
		models: {
			product_group: '\\App\\Http\\Models\\ProductCategory',
			customer: '\\App\\Http\\Models\\Customer',
			product: '\\App\\Http\\Models\\Product',
		},
		cashier: null,
		permissions: {},
	}
}
