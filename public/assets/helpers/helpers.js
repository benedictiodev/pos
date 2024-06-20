const format_rupiah = (nominal) => {
    const result = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(nominal);

    return result.split(",")[0];
};

function update_to_format_rupiah(nominal) {
    nominal = nominal.toString();
    var number_string = nominal.replace(/[^,\d]/g, "").toString();
    var split = number_string.split(",");
    var sisa = split[0].length % 3;
    var rupiah = split[0].substr(0, sisa);
    var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return rupiah;
}
function keyup_rupiah(element) {
    element.value = update_to_format_rupiah(element.value);
}
