import {createFileRoute} from '@tanstack/react-router'
import {ProductForm} from "@/components/product-form";
import {ChangeEvent} from "react";
import {addProduct} from "@/api";

export const Route = createFileRoute('/addProduct')({
    component: () => AddProduct()
})

function AddProduct() {
    const handleAdd = async (data) => {
        const response = await addProduct(data);
    }

    return (
        <>
            <ProductForm onSubmit={handleAdd}/>
        </>
    )
}