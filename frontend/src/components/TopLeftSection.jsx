// TopLeftSection.js
import React from 'react';
import { useStats } from './StatsContext';

const TopLeftSection = () => {
    const { activeCount, inactiveCount, loading, error } = useStats();

    return (
        <div className="top-left-container">


            {error && <div className="error-message">{error}</div>}

            {!error && (
                <>
                    <div className="count-row">Active Count: {loading ? 'loading' : activeCount}</div>
                    <div className="count-row">Inactive Count: {loading ? 'loading' : inactiveCount}</div>
                </>
            )}

        </div>
    );
};

export default TopLeftSection;
