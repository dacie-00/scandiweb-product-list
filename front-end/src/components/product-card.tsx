import React from "react";
import {Product} from "../models/product";
import {Checkbox} from "@/components/ui/checkbox";

const specialAttributes = {
    book: (product) =>
        <>
            <p>Weight: {product.weight} KG</p>
        </>,
    dvd: (product) =>
        <>
        <p>Size: {product.size} MB</p>
        </>,
    furniture: (product) =>
        <>
            <p>Width: {product.width} CM</p>
            <p>Height: {product.height} CM</p>
            <p>Length: {product.length} CM</p>
        </>
};

export function ProductCard({product}: {product: Product}) {
    return (
        <div className="border-2 border-black p-2 aspect-square">
            <Checkbox className="delete-checkbox" onCheckedChange={(state) => product.toDelete = Boolean(state)}></Checkbox>
            <div className="text-center text-lg flex flex-col justify-center h-full">
                <p>{product.sku}</p>
                <p>{product.name}</p>
                <p>{product.price}</p>
                {specialAttributes[product.type] && specialAttributes[product.type](product)}
            </div>
        </div>
    );
}