// TopLeftSection.js
import React from 'react';
import { useStats } from './StatsContext';

const TopLeftSection = () => {
    const { activeCount, inactiveCount, loading, error } = useStats();

    return (
        <div className="top-left-container">
          
            {loading && <div className="spinner"></div>}

            {error && <div className="error-message">{error}</div>}

            {!loading && !error && (
                <>
                    <div className="count-row">Active Count: {activeCount}</div>
                    <div className="count-row">Inactive Count: {inactiveCount}</div>
                </>
            )}

        </div>
    );
};

export default TopLeftSection;
