const { createApp } = Vue


createApp({
  data() {
    return {
      title: 'Centrasoft Library',
      users: null,
      books: null,
      genres: null,
      records: null
    }
  },
  created() {
    // fetch on init
    this.fetchData()
  },
  mounted() {
    
  },
  methods: {
    async fetchData() {
      this.users = await (await fetch(`/user`)).json()
      //console.log(this.users[0].attributes);
      this.genres = await (await fetch(`/genre`)).json()
      this.books = await (await fetch(`/book`)).json()
      this.records = await (await fetch(`/record`)).json()
    },
    addUser() {
      alert('Added');
    }
  }
}).mount('#app')