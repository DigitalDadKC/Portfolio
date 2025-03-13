export function useFormatCurrency() {
    const formatCurrency = ((value) => {
        return (value) ? value.toLocaleString('en-US', {
            style: 'currency',
            currency: 'USD'
        }) : ''
    })

    return { formatCurrency }
}