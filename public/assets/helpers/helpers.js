const format_rupiah = (nominal) => {
    const result = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(nominal);

    return result.split(",")[0];
};
