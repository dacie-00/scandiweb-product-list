import axios from "axios";
import { Product } from "./models/product";

export async function fetchProducts() {
    return await axios.get(
        'http://localhost:8000/products'
    )
        .then((response) => response.data);
}

export async function deleteProducts(products: Product[]) {
    const productIds = products.map(product => product.id);
    return await axios.delete(
        'http://localhost:8000/products',
        {data: {products: productIds}}
        );
}

export async function addProduct(product: Product) {
    return await axios.post(
        'http://localhost:8000/products',
        {product: product}
    )
}