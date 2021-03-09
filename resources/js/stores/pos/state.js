export default function () {
	return {
		models: {
			payment_method: '\\App\\Http\\Models\\PaymentMethod',
			customer: '\\App\\Http\\Models\\Customer',
			product: '\\App\\Http\\Models\\Product',
		},
		payment_methods: [],
		cashier: null,
		permissions: {},
	}
}
