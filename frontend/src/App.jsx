import React from 'react';

import { StatsProvider } from './components/StatsContext';
import TopLeftSection from './components/TopLeftSection';
import TopRightSection from './components/TopRightSection';
import Paginator from './components/Paginator';
import FilterComponent from './components/Filter';
import Grid from './components/Grid';

const App = () => (
  <StatsProvider>
    <div class="header">
      <TopLeftSection />
      <TopRightSection />
    </div>
    <FilterComponent />
    <Grid />
    <Paginator />

  </StatsProvider>
);


export default App
