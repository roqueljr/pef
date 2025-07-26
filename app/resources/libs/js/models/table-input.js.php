<script>
class tb {
    static input(
        input_type,
        type = 'text',
        label = 'Input here...'
    ) {
        let td = '';

        if (input_type === 'input') {
            td = `
                <td>
                    <input type="${type}" placeholder="${label}" >
                </td>
            `;
        }

        if (input_type === 'select') {

        }
    }

}
</script>