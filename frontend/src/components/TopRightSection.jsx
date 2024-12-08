import React from 'react';
import { useStats } from './StatsContext';

const TopRightSection = () => {
    const { fetchAll } = useStats();

    return (
        <div className="top-right-container">
            <button className="refresh-button" onClick={() => { 
                fetchAll(); 
                }}>
                Refresh
            </button>
        </div>
    );
};

export default TopRightSection;