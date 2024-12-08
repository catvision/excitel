// Paginator.js
import React from 'react';
import { useStats } from './StatsContext';

const Paginator = () => {
    const { currentPage, totalPages, awaitableSetState, fetchPlans } = useStats();


    // Pagination functions
    const goToPage = (page) => {
        if (page >= 1 && page <= totalPages) {
            awaitableSetState('currentPage', page)
                .then((refpage) => { fetchPlans(); });
        }
    };

    const nextPage = () => {
        if (currentPage < totalPages) {
            awaitableSetState('currentPage', currentPage + 1)
                .then((refpage) => { fetchPlans(); });
        }
    };

    const prevPage = () => {

        if (currentPage > 1) {
            awaitableSetState('currentPage', currentPage - 1)
                .then((refpage) => { fetchPlans(); });
        }
    };

    // Generate an array of page numbers
    const pageNumbers = Array.from({ length: totalPages }, (_, index) => index + 1);

    return (
        <div className="paginator-container">
            <button
                className="paginator-button"
                onClick={prevPage}
                disabled={currentPage === 1}
            >
                Previous
            </button>

            <div className="page-numbers">
                {pageNumbers.map((pageNumber) => (
                    <button
                        key={pageNumber}
                        className={`page-number ${currentPage === pageNumber ? 'active' : ''}`}
                        onClick={() => goToPage(pageNumber)}
                    >
                        {pageNumber}
                    </button>
                ))}
            </div>

            <button
                className="paginator-button"
                onClick={nextPage}
                disabled={currentPage === totalPages}
            >
                Next
            </button>
        </div>
    );
};

export default Paginator;
