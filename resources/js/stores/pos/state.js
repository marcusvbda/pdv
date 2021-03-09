export default function () {
	return {
		models: {
			payment_method: '\\App\\Http\\Models\\PaymentMethod',
			customer: '\\App\\Http\\Models\\Customer',
			product: '\\App\\Http\\Models\\Product',
			sale: '\\App\\Http\\Models\\Sale',
		},
		payment_methods: [],
		cashier: null,
		permissions: {},
	}
}
