<template lang="html">
  <div class="document-stats">
    <div class="row">
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-file-alt"></i>
          </div>
          <div class="stat-content">
            <h3>{{ totalDocuments }}</h3>
            <p>{{ $t("documents.total_documents") }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-icon success">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="stat-content">
            <h3>{{ signedDocuments }}</h3>
            <p>{{ $t("documents.signed") }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-icon warning">
            <i class="fas fa-clock"></i>
          </div>
          <div class="stat-content">
            <h3>{{ pendingDocuments }}</h3>
            <p>{{ $t("documents.pending") }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-card">
          <div class="stat-icon danger">
            <i class="fas fa-times-circle"></i>
          </div>
          <div class="stat-content">
            <h3>{{ cancelledDocuments }}</h3>
            <p>{{ $t("documents.cancelled") }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DocumentStats',
  props: {
    documents: {
      type: Array,
      default: () => []
    }
  },
  computed: {
    totalDocuments() {
      return this.documents.length;
    },
    signedDocuments() {
      return this.documents.filter(doc => doc.status === 'signed').length;
    },
    pendingDocuments() {
      return this.documents.filter(doc => doc.status === 'pending').length;
    },
    cancelledDocuments() {
      return this.documents.filter(doc => doc.status === 'cancelled').length;
    }
  }
}
</script>

<style scoped>
.document-stats {
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border-radius: 10px;
  padding: 1.5rem;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  transition: transform 0.2s;
  height: 100%;
}

.stat-card:hover {
  transform: translateY(-2px);
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  background-color: #e9ecef;
  color: #6c757d;
  font-size: 1.5rem;
}

.stat-icon.success {
  background-color: #d4edda;
  color: #155724;
}

.stat-icon.warning {
  background-color: #fff3cd;
  color: #856404;
}

.stat-icon.danger {
  background-color: #f8d7da;
  color: #721c24;
}

.stat-content h3 {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  color: #2c3e50;
}

.stat-content p {
  margin: 0;
  color: #6c757d;
  font-size: 0.9rem;
}

@media (max-width: 768px) {
  .stat-card {
    margin-bottom: 1rem;
  }

  .stat-icon {
    width: 50px;
    height: 50px;
    font-size: 1.2rem;
  }

  .stat-content h3 {
    font-size: 1.5rem;
  }
}
</style>
