<template>
  <div>
    <div class="container" v-if="loading">loading...</div>

    <div class="container" v-if="!loading">
      <div class="text-center pt-5 pb-2">
        <img
          src="https://upload.wikimedia.org/wikipedia/en/thumb/b/bb/Lietuvos_Bankas_Logo.svg/1200px-Lietuvos_Bankas_Logo.svg.png"
          width="200"
          class="img-fluid"
          alt="Responsive image"
        />
      </div>

      <b-card :header="'Welcome, ' + account.name" class="mt-3">
        <b-card-text>
          <div>
            Account Id:
            <code>#{{ account.id }}</code>
          </div>
          <div>
            Balance:
            <code>{{ currencySymbol}} {{ account.balance }}</code>
          </div>
        </b-card-text>
        <b-button size="sm" variant="success" @click="show = !show">New payment</b-button>

        <b-button class="float-right" variant="danger" size="sm" nuxt-link to="/">Logout</b-button>
      </b-card>

      <transition name="fade">
        <b-card class="mt-3" header="New Payment" v-show="show">
          <b-form @submit.prevent="onSubmit">
            <div class="alert alert-danger" v-if="errors.length > 0">
              <p v-for="(error, index) in errors" :key="index">{{error}}</p>
            </div>
            <b-form-group id="input-group-1" label="To:" label-for="input-1">
              <b-form-input
                id="input-1"
                size="sm"
                v-model="payment.to"
                type="number"
                required
                placeholder="Destination ID"
              ></b-form-input>
            </b-form-group>

            <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
              <b-input-group prepend="$" size="sm">
                <b-form-input
                  id="input-2"
                  v-model="payment.amount"
                  type="number"
                  required
                  placeholder="Amount"
                ></b-form-input>
              </b-input-group>
            </b-form-group>

            <b-form-group id="input-group-3" label="Details:" label-for="input-3">
              <b-form-input
                id="input-3"
                size="sm"
                v-model="payment.details"
                required
                placeholder="Payment details"
              ></b-form-input>
            </b-form-group>

            <b-button type="submit" size="sm" variant="primary">Submit</b-button>
          </b-form>
        </b-card>
      </transition>

      <b-card class="mt-3" header="Payment History">
        <b-table striped hover :fields="fields" :items="transactions"></b-table>
      </b-card>
    </div>
  </div>
</template>

<script lang="ts">
import axios from "axios";
import Vue from "vue";
import moment from 'moment'

export default {
  data() {
    return {
      show: false,
      payment: {},

      account: {},
      transactions: [],

      loading: true,
      ApiEndpoint: process.env.API_URL,
      userId: this.$route.params.id,
      errors: [],
      fields: [
        { key: "from", label: "From (Source)" },
        { key: "to", label: "To (Destination)" },
        { key: "details" },
        {
          key: "amount",
          formatter: (value, key, item) => {
            let symbol = this.account.id != item.to ? "-" : "";

            return symbol + this.currencySymbol + value;
          },
        },
        { key: "created_at", label: "Transfered at",
        formatter: (value, key, item) => {
          return moment(String(value)).format('Y-MM-DD')
        }
        },
      ],
    };
  },
  computed: {
    currencySymbol: function () {
      return this.account.currency == "usd" ? "$" : "€";
    },
  },
  mounted() {
    this.mountAccountData();
    this.mountTransactionsData();
  },

  methods: {
    async onSubmit() {
      this.errors = [];

      try {
        let create = await this.createTransaction(this.payment);

        if (create.status == 201) {
          alert("Payment created successfully");

          this.resetPaymentForm();
          this.mountAccountData();
          this.mountTransactionsData();
        }
      } catch (error) {
        this.errors.push(error.response.data.errors);
      }
    },
    async getAccount(id) {
      return await axios.get(`${this.ApiEndpoint}/accounts/${id}`);
    },
    async getTransactionsByAccount(id) {
      return await axios.get(`${this.ApiEndpoint}/accounts/${id}/transactions`);
    },
    async createTransaction(paymentData) {
      return await axios.post(
        `${this.ApiEndpoint}/accounts/${this.userId}/transactions`,
        paymentData
      );
    },
    resetPaymentForm() {
      this.payment = {};
    },
    async mountAccountData() {
      try {
        let response = await this.getAccount(this.userId);
        this.account = response.data.result;
        this.loading = false;
      } catch (error) {
        this.$router.push('/')
      }
    },
    async mountTransactionsData() {
      try {
        let response = await this.getTransactionsByAccount(this.userId);
        this.transactions = response.data.result.data;
      } catch (error) {
        this.transactions = [];
      }
    },
  },
};
</script>
