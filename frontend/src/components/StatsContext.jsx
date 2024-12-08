
import React, { createContext, useCallback, useContext, useRef, useState,useEffect } from 'react';


const StatsContext = createContext();


export const StatsProvider = ({ children }) => {


    const stateRef = useRef({
        currentPage: 1,
        filterType :"byName",
        inputValue :"",
        dbPlans: [],
        /*private variable con't use awaitableSetState with them */
        prevInputValue:""    
    });

    const [state, setState] = useState({
        activeCount: null,
        inactiveCount: null,
        dbPlans: [],
        loading: true,
        error: null,
        currentPage: 1,
        totalPages: 10,
        pageSize: 5,
        filterType :"byName",
        inputValue :""
    });

    

    // Define the fetchStats function using useCallback to memoize it
    const fetchStats = useCallback(async () => {

        setState(prevState => { return { ...prevState, loading: true }; });
        setState(prevState => { return { ...prevState, error: null }; });

        try {
            const response = await fetch('/backend/index.php?statusStats', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // // Validate response structure
            // if (typeof data.activeCount !== 'number' || typeof data.inactiveCount !== 'number') {
            //     throw new Error('Invalid data format received from backend.');
            // }

            setState(prevState => { return { ...prevState, activeCount: data.activeCount }; });
            setState(prevState => { return { ...prevState, inactiveCount: data.inactiveCount }; });
        } catch (err) {
            console.error('Failed to fetch stats:', err);
            setState(prevState => { return { ...prevState, error: err.message }; });
        } finally {
            setState(prevState => { return { ...prevState, loading: false }; });
        }
    }, []);

    const fetchPlans = useCallback(async () => {

        setState(prevState => { return { ...prevState, loading: true }; });
        setState(prevState => { return { ...prevState, error: null }; });
        try {

            let formData;
            if (typeof window === "undefined") {
                const ServerFormData = require("form-data");
                formData = new ServerFormData();
            } else {
                formData = new FormData();
            }

            if(stateRef.current.prevInputValue!==stateRef.current.inputValue)
            {
               await awaitableSetState("currentPage",1);
               stateRef.current.prevInputValue=stateRef.current.inputValue;
            }


            let page = (stateRef.current.currentPage-1)*state.pageSize;
            formData.append('page', page);

            formData.append('subString',stateRef.current.inputValue);
            formData.append('filterType',stateRef.current.filterType);

            const response = await fetch('/backend/index.php?getPlans', {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            //refresh pages count
            if(!data.rowsCount)
            {
                setState(prevState => { return { ...prevState, totalPages: 0 }; });
            }    
            else{
                let pc = Math.ceil(data.rowsCount / state.pageSize);
                setState(prevState => { return { ...prevState, totalPages: pc }; });
            }
            setState(prevState => { return { ...prevState, dbPlans: data.items }; });

        } catch (err) {
            console.error('Failed to fetch stats:', err);
            setState(prevState => { return { ...prevState, error: err.message }; });
        } finally {
            setState(prevState => { return { ...prevState, loading: false }; });
        }
    },);

    const fetchProxy = useCallback(async () => {

        setState(prevState => { return { ...prevState, loading: true }; });
        setState(prevState => { return { ...prevState, error: null }; });

        try {
            const response = await fetch('/backend/index.php?cron', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

           // const data = await response.json();

            
        } catch (err) {
            console.error('Failed to fetch stats:', err);
            setState(prevState => { return { ...prevState, error: err.message }; });
        } finally {
            setState(prevState => { return { ...prevState, loading: false }; });
        }
    }, []);

    const fetchAll = async () =>{
        await fetchProxy();
        await fetchStats();
        await fetchPlans();
    }

    const calcPrice = () => {
        return new Promise((resolve) => {

            setState(prevState => {
                return { ...prevState, [key]: value };
            });
            setRef(key, value);

            resolve(value);
        });
    };


    const setRef = useCallback((key, value) => {
        if (stateRef.current[key] !== undefined) {
            stateRef.current[key] = value;
        }
    }, []);

    useEffect(() => {

        fetchStats();
        fetchPlans();
    }, []);



    const awaitableSetState = (key, value) => {
        return new Promise((resolve) => {

            setState(prevState => {
                return { ...prevState, [key]: value };
            });
            setRef(key, value);

            resolve(value);
        });
    };

    return (
        <StatsContext.Provider value={{ ...state, awaitableSetState, fetchStats, fetchPlans, fetchAll }}>
            {children}
        </StatsContext.Provider>
    );
};

/**
 * useStats
 * Custom hook to consume StatsContext.
 */
export const useStats = () => {
    return useContext(StatsContext);
};
