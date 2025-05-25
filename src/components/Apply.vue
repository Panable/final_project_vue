<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h2 class="display-4 mb-4">Application Form</h2>
            <form 
              class="needs-validation" 
              novalidate
              method="post" 
              action="http://mercury.swin.edu.au/it000000/formtest.php"
              @submit.prevent="submitForm"
            >
              <fieldset class="mb-4">
                <legend class="h5 mb-3">Personal Information</legend>

                <div class="row g-3">
                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.firstName" type="text" class="form-control" id="firstName" name="firstName" required />
                      <label for="firstName">First Name</label>
                      <div v-if="errors.firstName" class="invalid-feedback d-block">{{ errors.firstName }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.lastName" type="text" class="form-control" id="lastName" name="lastName" required />
                      <label for="lastName">Last Name</label>
                      <div v-if="errors.lastName" class="invalid-feedback d-block">{{ errors.lastName }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.username" type="text" class="form-control" id="username" name="username" required />
                      <label for="username">Username</label>
                      <div v-if="errors.username" class="invalid-feedback d-block">{{ errors.username }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.email" type="email" class="form-control" id="email" name="email" required />
                      <label for="email">Email</label>
                      <div v-if="errors.email" class="invalid-feedback d-block">{{ errors.email }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.password" type="password" class="form-control" id="password" name="password" required />
                      <label for="password">Password</label>
                      <div v-if="errors.password" class="invalid-feedback d-block">{{ errors.password }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.confirmPassword" type="password" class="form-control" id="confirmPassword" name="confirmPassword" required />
                      <label for="confirmPassword">Confirm Password</label>
                      <div v-if="errors.confirmPassword" class="invalid-feedback d-block">{{ errors.confirmPassword }}</div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="mb-4">
                <legend class="h5 mb-3">Address Information</legend>

                <div class="row g-3">
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input v-model="form.streetAddress" type="text" class="form-control" id="streetAddress" name="streetAddress" maxlength="40" />
                      <label for="streetAddress">Street Address</label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.suburb" type="text" class="form-control" id="suburb" name="suburb" maxlength="20" />
                      <label for="suburb">Suburb</label>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.postcode" type="text" class="form-control" id="postcode" name="postcode" required />
                      <label for="postcode">Postcode</label>
                      <div v-if="errors.postcode" class="invalid-feedback d-block">{{ errors.postcode }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.mobile" type="text" class="form-control" id="mobile" name="mobile" required />
                      <label for="mobile">Mobile Number</label>
                      <div v-if="errors.mobile" class="invalid-feedback d-block">{{ errors.mobile }}</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-floating mb-3">
                      <input v-model="form.dob" type="date" class="form-control" id="dob" name="dob" required />
                      <label for="dob">Date of Birth</label>
                      <div v-if="errors.dob" class="invalid-feedback d-block">{{ errors.dob }}</div>
                    </div>
                  </div>
                </div>
              </fieldset>

              <fieldset class="mb-4">
                <legend class="h5 mb-3">Job Preferences</legend>

                <div class="form-floating mb-3">
                  <select v-model="form.category" class="form-select" id="category" name="category" required>
                    <option disabled value="">Select a category</option>
                    <option>AI</option>
                    <option>Data Science</option>
                    <option>Software Development</option>
                    <option>Cybersecurity</option>
                    <option>DevOps</option>
                  </select>
                  <label for="category">Preferred Job Category</label>
                  <div v-if="errors.category" class="invalid-feedback d-block">{{ errors.category }}</div>
                </div>
              </fieldset>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-send me-2"></i>Submit
                </button>
                <button type="button" @click="showTerms = !showTerms" class="btn btn-outline-secondary">
                  <i class="bi bi-file-text me-2"></i>{{ showTerms ? 'Hide Terms' : 'Show Terms' }}
                </button>
              </div>

              <div v-if="showTerms" class="mt-4 p-3 bg-light rounded">
                <h6 class="mb-2">Terms and Conditions:</h6>
                <p class="mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'

const form = reactive({
  firstName: '',
  lastName: '',
  username: '',
  password: '',
  confirmPassword: '',
  email: '',
  streetAddress: '',
  suburb: '',
  postcode: '',
  mobile: '',
  dob: '',
  category: ''
})

const errors = reactive({})
const showTerms = ref(false)

function validateForm() {
  errors.firstName = /^[A-Za-z]+$/.test(form.firstName) ? '' : 'First Name must contain only letters'
  errors.lastName = /^[A-Za-z]+$/.test(form.lastName) ? '' : 'Last Name must contain only letters'
  errors.username = form.username.length >= 3 ? '' : 'Username must be at least 3 characters'
  errors.password = /^(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/.test(form.password) ? '' : 'Password must be at least 8 characters and contain a special character'
  errors.confirmPassword = form.confirmPassword === form.password ? '' : 'Passwords do not match'
  errors.email = /^\S+@\S+\.\S+$/.test(form.email) ? '' : 'Invalid email format'
  errors.postcode = /^\d{4}$/.test(form.postcode) ? '' : 'Postcode must be exactly 4 digits'
  errors.mobile = /^04\d{8}$/.test(form.mobile) ? '' : 'Mobile must start with 04 and have 10 digits'
  errors.dob = form.dob && (new Date().getFullYear() - new Date(form.dob).getFullYear()) >= 16 ? '' : 'You must be at least 16 years old'
  errors.category = form.category ? '' : 'Please select a category'

  return Object.values(errors).every(e => !e)
}

function submitForm() {
  if (validateForm()) {
    document.querySelector('form').submit()
  }
}
</script>

<style scoped>
.form-control:focus,
.form-select:focus {
  border-color: #0d6efd;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
  background-color: #0d6efd;
  border-color: #0d6efd;
}

.btn-primary:hover {
  background-color: #0b5ed7;
  border-color: #0a58ca;
}

.btn-outline-secondary:hover {
  background-color: #6c757d;
  border-color: #6c757d;
  color: white;
}
</style> 