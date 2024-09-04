import {createFileRoute} from '@tanstack/react-router'
import {deleteProducts, fetchProducts} from "@/api";
import {useMutation, useQuery, useQueryClient,} from '@tanstack/react-query'
import {ProductList} from "@/components/product-list";
import React from "react";

export const Route = createFileRoute('/')({
    component: () => Index(),
})

function Index() {
    const queryClient = useQueryClient();

    const {isPending, error, data} = useQuery({
        queryKey: ['products'],
        queryFn: fetchProducts
    })

    const deleteMutation = useMutation({
        mutationFn: deleteProducts,
        onSuccess: () => {
            queryClient.invalidateQueries({queryKey: ['products']})
        }
    })

    if (isPending) return 'Loading...'

    if (error) return 'An error has occurred: ' + error.message

    return (
        <>
            <div className="mx-auto max-w-4xl">
                <ProductList products={data} onDelete={deleteMutation.mutate}/>
            </div>
        </>
    )
}