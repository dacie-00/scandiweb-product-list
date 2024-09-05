import axios from "axios";
import { Product } from "./models/product";

const $baseUrl = "https://scandiweb-products-api-1085462946921.europe-north1.run.app/"

export async function fetchProducts() {
    return await axios.get(
        $baseUrl + 'products'
    )
        .then((response) => response.data);
}

export async function deleteProducts(products: Product[]) {
    const productIds = products.map(product => product.id);
    return await axios.delete(
        $baseUrl + 'products',
        {data: {products: productIds}}
        );
}

export async function addProduct(product: Product) {
    return await axios.post(
        $baseUrl + 'products',
        {product: product}
    )
}