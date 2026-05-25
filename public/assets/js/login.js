const validator = new JustValidate("#login");
validator.addField(
    '#floatingInput', [
    {
        rule: 'required',
        errorMessage: 'Se requiere una boleta'
    }
]).addField(
    '#floatingPassword', [
    {
        rule: 'required',
        errorMessage: 'Se requiere una contraseña'
    },
]);