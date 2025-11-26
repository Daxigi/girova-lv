
export interface CartItem {
    id: number | string;      
    name: string;             
    price: number;                  
    quantity: number;
    stock: number;             
    description?: string;     
}

export interface Cart {
    items: CartItem[];         
    total: number;            
    itemCount: number;        
}
