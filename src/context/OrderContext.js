import { createContext, useContext, useState, useCallback } from 'react';

const OrderContext = createContext(null);

const initialState = {
  name: '',
  phone: '',
  service_id: null,
  notes: '',
  photo_link: '',
  ticket_id: null,
};

export function OrderProvider({ children }) {
  const [orderState, setOrderState] = useState(initialState);

  const setStep1 = useCallback(({ name, phone, service_id, notes }) => {
    setOrderState(prev => ({ ...prev, name, phone, service_id, notes }));
  }, []);

  const setStep2 = useCallback(({ photo_link }) => {
    setOrderState(prev => ({ ...prev, photo_link }));
  }, []);

  const setTicket = useCallback((ticket_id) => {
    setOrderState(prev => ({ ...prev, ticket_id }));
  }, []);

  const reset = useCallback(() => {
    setOrderState(initialState);
  }, []);

  return (
    <OrderContext.Provider value={{ orderState, setStep1, setStep2, setTicket, reset }}>
      {children}
    </OrderContext.Provider>
  );
}

export function useOrder() {
  const context = useContext(OrderContext);
  if (!context) {
    throw new Error('useOrder must be used within OrderProvider');
  }
  return context;
}
