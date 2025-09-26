<template lang="html">
  <div class="document-filter">
    <div class="row">
      <div class="col-md-6">
        <div class="search-box">
          <input
            type="text"
            class="form-control"
            :placeholder="$t('documents.search_placeholder')"
            v-model="searchQuery"
            @input="onSearchChange"
          >
          <i class="fas fa-search search-icon"></i>
        </div>
      </div>
      <div class="col-md-6">
        <div class="filter-controls">
          <div class="filter-buttons">
            <button
              class="btn btn-outline-primary btn-sm me-2"
              :class="{ active: activeFilter === 'all' }"
              @click="setFilter('all')"
            >
              {{ $t("documents.all_documents") }}
            </button>
            <button
              class="btn btn-outline-success btn-sm me-2"
              :class="{ active: activeFilter === 'signed' }"
              @click="setFilter('signed')"
            >
              {{ $t("documents.signed") }}
            </button>
            <button
              class="btn btn-outline-warning btn-sm me-2"
              :class="{ active: activeFilter === 'pending' }"
              @click="setFilter('pending')"
            >
              {{ $t("documents.pending") }}
            </button>
            <button
              class="btn btn-outline-danger btn-sm"
              :class="{ active: activeFilter === 'cancelled' }"
              @click="setFilter('cancelled')"
            >
              {{ $t("documents.cancelled") }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DocumentFilter',
  props: {
    value: {
      type: String,
      default: ''
    },
    filter: {
      type: String,
      default: 'all'
    }
  },
  data() {
    return {
      searchQuery: this.value,
      activeFilter: this.filter
    }
  },
  watch: {
    value(newVal) {
      this.searchQuery = newVal;
    },
    filter(newVal) {
      this.activeFilter = newVal;
    }
  },
  methods: {
    onSearchChange() {
      this.$emit('input', this.searchQuery);
      this.$emit('search', this.searchQuery);
    },
    setFilter(filter) {
      this.activeFilter = filter;
      this.$emit('filter-change', filter);
    }
  }
}
</script>

<style scoped>
.document-filter {
  margin-bottom: 2rem;
}

.search-box {
  position: relative;
}

.search-box input {
  padding-right: 3rem;
  border-radius: 25px;
  border: 2px solid #e9ecef;
  transition: all 0.3s ease;
}

.search-box input:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.search-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6c757d;
}

.filter-controls {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  height: 100%;
}

.filter-buttons .btn {
  border-radius: 20px;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.3s ease;
  border-width: 2px;
}

.filter-buttons .btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.filter-buttons .btn.active {
  background-color: #007bff;
  border-color: #007bff;
  color: white;
  box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.filter-buttons .btn-outline-success.active {
  background-color: #28a745;
  border-color: #28a745;
}

.filter-buttons .btn-outline-warning.active {
  background-color: #ffc107;
  border-color: #ffc107;
  color: #212529;
}

.filter-buttons .btn-outline-danger.active {
  background-color: #dc3545;
  border-color: #dc3545;
}

@media (max-width: 768px) {
  .filter-controls {
    justify-content: flex-start;
    margin-top: 1rem;
  }

  .filter-buttons .btn {
    margin-bottom: 0.5rem;
    margin-right: 0.5rem;
  }
}
</style>
