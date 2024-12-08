import React, { useState,  useRef } from 'react';
import { useStats } from './StatsContext';

const FilterComponent = () => {
   
    const { filterType,inputValue, awaitableSetState, fetchPlans } = useStats(); 
  // Refs for the dropdown and input elements
  const dropdownRef = useRef(null);
  const inputRef = useRef(null);

  // State to track the user's input and the hint display
//   const [filterType, setFilterType] = useState('byName');
//   const [inputValue, setInputValue] = useState('');
  const [showHint, setShowHint] = useState(false);
  let typingTimer = useRef(null);

  // Handle changes in the dropdown
  const handleDropdownChange = (event) => {
    //setFilterType(event.target.value);
    awaitableSetState("filterType",event.target.value);
  };

  // Handle changes in the input
  const handleInputChange = (event) => {
    const value = event.target.value;
    awaitableSetState("inputValue",value);
    setShowHint(false); // Reset hint display

    // Clear the previous timer
    if (typingTimer.current) {
      clearTimeout(typingTimer.current);
    }

    // Set a new timer to show the hint after 200ms of no typing
    typingTimer.current = setTimeout(() => {
      if (value) {
        setShowHint(true);
      }
    }, 350);
  };

  // Handle when the user presses Enter
  const handleKeyPress = (event) => {
    if (event.key === 'Enter') {
      //doFilter();
      if (typingTimer.current) {
        clearTimeout(typingTimer.current);
      }
      setShowHint(false); // Hide hint after search
      fetchPlans();
    }
  };

  // Function to simulate filtering
//   const doFilter = () => {
//     console.log(`Filtering by ${filterType} with value: ${inputValue}`);
//     // Here you can call an API, update state, or perform any action you want
//     setShowHint(false); // Hide hint after search
//   };

  return (
    <div className="filter-box">
      <div >
        <select
          ref={dropdownRef}
          value={filterType}
          onChange={handleDropdownChange}
          
        >
          <option value="byName">By Name</option>
          <option value="byCategory">By Category</option>
        </select>

        <input
          ref={inputRef}
          type="text"
          value={inputValue}
          onChange={handleInputChange}
          onKeyDown={handleKeyPress}
          placeholder="Type to search..."
          
        />

        {showHint && <div className="hint">Press Enter to search</div>}
      </div>
    </div>
  );
};

export default FilterComponent;
