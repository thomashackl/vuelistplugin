<template>
    <div>
        <div class="messagebox messagebox_info" v-if="users.length < 1">
            Es wurden keine Benutzer gefunden.
        </div>
        <table class="default" v-else>
            <caption>
                Stud.IP-Benutzer
                <span class="actions">
                    <a @click="previousPage" v-if="page > 0">&lt;</a>
                    {{ page + 1 }} / {{ maxPages }}
                    <a @click="nextPage">&gt;</a>
                </span>
            </caption>
            <colgroup>
                <col width="25%">
                <col width="25%">
                <col width="10%">
                <col width="20%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th>Nachname</th>
                    <th>Vorname</th>
                    <th>Username</th>
                    <th>Letzter Login</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.user_id">
                    <td>
                        <input type="text" v-model="user.Nachname" v-if="editing.indexOf(user.user_id) !== -1"/>
                        <span v-else>{{ user.Nachname }}</span>
                    </td>
                    <td>
                        <input type="text" v-model="user.Vorname" v-if="editing.indexOf(user.user_id) !== -1"/>
                        <span v-else>{{ user.Vorname }}</span>
                    </td>
                    <td>
                        <input type="text" v-model="user.username" v-if="editing.indexOf(user.user_id) !== -1"/>
                        <span v-else>{{ user.username }}</span>
                    </td>
                    <td>{{ user.lastlogin }}</td>
                    <td>
                        <span v-if="editing.indexOf(user.user_id) !== -1">
                            <button>Speichern</button>
                            <button @click="cancelEdit(user)">Abbrechen</button>
                        </span>
                        <span v-else>
                            <button @click="editUser(user)">Bearbeiten</button>
                            <button>Löschen</button>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: 'user-table',
        data() {
            return {
                users: [],
                editing: [],
                page: 0,
                maxPages: 1,
                start: 0,
                limit: 100
            }
        },
        mounted: function () {
            this.usersURL = document.getElementById('data-container').dataset.getUsersUrl
            this.getUsers(this.start, this.limit)
            this.getUsersCount()
        },
        methods: {
            async getUsers(start, limit) {
                try {
                    this.start = start
                    this.limit = limit
                    const response = await fetch(this.usersURL + '/' + start + '/' + limit)
                    const data = await response.json()
                    this.users = data
                } catch (error) {
                    // eslint-disable-next-line no-console
                    console.error(error)
                }
            },
            async getUsersCount() {
                const countURL = document.getElementById('data-container').dataset.getCountUrl
                const response = await fetch(countURL)
                const data = await response.json()
                this.maxPages = Math.ceil(data.count / this.limit);
            },
            editUser: function(user) {
                this.editing.push(user.user_id)
            },
            cancelEdit: function(user) {
                this.editing.splice(this.editing.indexOf(user.user_id), 1)
            },
            previousPage() {
                this.getUsers(this.start - this.limit, this.limit)
                this.page--;
            },
            nextPage() {
                this.getUsers(this.start + this.limit, this.limit)
                this.page++;
            }
        }
    }
</script>

<style scoped>
    span.actions a {
        cursor: pointer;
    }
</style>
