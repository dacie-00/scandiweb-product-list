import React from "react";
import {Input} from "@/components/ui/input";
import {ProductCombobox} from "@/components/product-combobox";
import {Label} from "@/components/ui/label";
import {Button} from "@/components/ui/button";
import {Link} from "@tanstack/react-router";

const productTypes = [
    {
        value: "book",
        label: "Book",
    },
    {
        value: "dvd",
        label: "DVD",
    },
    {
        value: "furniture",
        label: "Furniture",
    },
]

const specialAttributes = {
    book: () =>
        <>
            <Label htmlFor="weight">Weight (KG)</Label>
            <Input inputMode="text" placeholder="Weight" name="weight" id="weight"/>
            <p>Please provide the weight in KG</p>
        </>,
    dvd: () =>
        <>
            <Label htmlFor="mb">Size (MB)</Label>
            <Input inputMode="decimal" placeholder="MB" name="mb" id="mb"/>
            <p>Please provide the size in MB</p>
        </>,
    furniture: () =>
        <>
            <Label htmlFor="height">Height (CM)</Label>
            <Input inputMode="decimal" placeholder="Height" name="height" id="height"/>
            <Label htmlFor="width">Width (CM)</Label>
            <Input inputMode="decimal" placeholder="Width" name="width" id="width"/>
            <Label htmlFor="length">Length (CM)</Label>
            <Input inputMode="decimal" placeholder="Length" name="length" id="length"/>
            <p>Please provide dimensions in HxWxL format</p>
        </>
};

type ProductFormProps = {
    onSubmit: (formData: any) => void
}

export function ProductForm({onSubmit}: ProductFormProps) {
    const [type, setType] = React.useState("")

    const handleSubmit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        const formData = new FormData(event.currentTarget);
        onSubmit(Object.fromEntries(formData.entries()));
    };

    return (
        <>
            <div className="flex items-center justify-between mb-4">
                <h1 className="text-2xl font-bold">Product List</h1>
                <div className="space-x-4">
                    <Button form="product_form" type="submit">Save</Button>
                    <Link to="/">
                        <Button>Cancel</Button>
                    </Link>
                </div>
            </div>
            <form id="product_form" onSubmit={handleSubmit} className={"space-y-8"}>
                <div className="w-100 flex items-center justify-between space-x-4">
                    <Label htmlFor="sku">SKU</Label>
                    <Input inputMode="text" placeholder="SKU" name="sku" id="sku"/>
                </div>
                <div className="w-100 flex items-center justify-between space-x-4">
                    <Label htmlFor="name">name</Label>
                    <Input inputMode="text" placeholder="Name" name="name" id="name"/>
                </div>
                <div className="w-100 flex items-center justify-between space-x-4">
                    <Label htmlFor="price">Price ($)</Label>
                    <Input inputMode="decimal" placeholder="Price" name="price" id="price"/>
                </div>
                <div className="w-100 flex items-center justify-between space-x-4">
                    <Label htmlFor="productType">Type</Label>
                    <ProductCombobox onChange={setType} name="type" id="productType"/>
                </div>
                {type && specialAttributes[type] && specialAttributes[type]()}
            </form>
        </>
    );
}