import React, { Component } from 'react'
import { Table } from 'semantic-ui-react'
import _ from 'lodash'

const API_URL = 'http://127.0.0.1:8000/api'

class ResultsList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            peopleData: [],
            groupData: [],
            peopleUploadFeedback: {
                message: '',
                type: ''
            },
            groupsUploadFeedback: {
                message: '',
                type: ''
            },
            groupColumn: null,
            groupDirection: null,
            peopleColumn: null,
            peopleDirection: null
        };
    }

    componentDidMount  = async () => {
        const peopleResponse = await fetch("http://localhost:8000/api/people")
        const peopleData = await peopleResponse.json()
        this.setState({
            peopleData: peopleData.data
        })
    }

    onPeopleFileSelected = async (event) => {
        // console.log(event.target.files[0])
        const formData = new FormData()
        formData.append('file', event.target.files[0])
        const uploadPeopleResponse = await fetch(API_URL + '/people', {
            method: 'POST',
            body: formData
        })
        if (uploadPeopleResponse.ok) {
            const responseData = await uploadPeopleResponse.json()
            this.setState({
                peopleData: responseData.collection.data,
                peopleUploadFeedback: {
                    message: responseData.feedback,
                    type: 'success'
                }
            })
        } else {
            this.setState({
                peopleUploadFeedback: 'Some error'
            })
        }
    }

    onGroupFileSelected = async (event) => {
        // console.log(event.target.files[0])
        const formData = new FormData()
        formData.append('file', event.target.files[0])
        const uploadGroupResponse = await fetch(API_URL + '/groups', {
            method: 'POST',
            body: formData
        })
        if (uploadGroupResponse.ok) {
            const responseData = await uploadGroupResponse.json()
            this.setState({
                groupData: responseData.collection.data,
                groupsUploadFeedback: {
                    message: responseData.feedback,
                    type: 'success'
                }
            })
        } else {
            this.setState({
                groupsUploadFeedback: 'Some error'
            })
        }
    }

    handlePeopleSort = (clickedColumn) => () => {
        const { peopleColumn, peopleData, peopleDirection } = this.state

        if (peopleColumn !== clickedColumn) {
          this.setState({
            peopleColumn: clickedColumn,
            peopleData: _.sortBy(peopleData, [clickedColumn]),
            peopleDirection: 'ascending',
          })

          return
        }

        this.setState({
          peopleData: peopleData.reverse(),
          peopleDirection: peopleDirection === 'ascending' ? 'descending' : 'ascending',
        })
    }

    handleGroupSort = (clickedColumn) => () => {
        const { groupColumn, groupData, groupDirection } = this.state

        if (groupColumn !== clickedColumn) {
          this.setState({
            groupColumn: clickedColumn,
            groupData: _.sortBy(groupData, [clickedColumn]),
            groupDirection: 'ascending',
          })

          return
        }

        this.setState({
          groupData: groupData.reverse(),
          groupDirection: groupDirection === 'ascending' ? 'descending' : 'ascending',
        })
    }

    render() {
        const {
            peopleUploadFeedback,
            groupsUploadFeedback,
            peopleData,
            groupData,
            peopleDirection,
            peopleColumn,
            groupDirection,
            groupColumn
        } = this.state
        return (
            <div>
                <div>
                    <label htmlFor="peopleCSVUpload">Upload a people list:</label><br /><br />
                    <input type="file"
                        id="peopleCSVUpload" name="peopleCSVUpload"
                        accept="text/csv"
                        onChange={this.onPeopleFileSelected}
                    />
                </div>
                <br />
                <div style={{ minHeight: 40 }}>
                    <span style={{ color: peopleUploadFeedback.type === 'success' ? 'green' : 'red' }}>
                        {peopleUploadFeedback.message}
                    </span>
                </div>
                <div>
                    <label htmlFor="groupCSVUpload">Upload a group list:</label><br /><br />
                    <input type="file"
                        id="groupCSVUpload" name="groupCSVUpload"
                        accept="text/csv"
                        onChange={this.onGroupFileSelected}
                    />
                </div>
                <br />
                <div style={{ minHeight: 40 }}>
                    <span style={{ color: groupsUploadFeedback.type === 'success' ? 'green' : 'red' }}>
                        {groupsUploadFeedback.message}
                    </span>
                </div>
                    <Table id="peopleTable" celled padded sortable style={{ marginBottom: 60 }}>
                        <Table.Header>
                            <Table.Row>
                            <Table.HeaderCell singleLine sorted={peopleColumn === 'first_name' ? peopleDirection : null} onClick={this.handlePeopleSort('first_name')}>First Name</Table.HeaderCell>
                            <Table.HeaderCell sorted={peopleColumn === 'last_name' ? peopleDirection : null} onClick={this.handlePeopleSort('last_name')}>Last Name</Table.HeaderCell>
                            <Table.HeaderCell sorted={peopleColumn === 'email_address' ? peopleDirection : null} onClick={this.handlePeopleSort('email_address')}>Email</Table.HeaderCell>
                            <Table.HeaderCell sorted={peopleColumn === 'status' ? peopleDirection : null} onClick={this.handlePeopleSort('status')}>Status</Table.HeaderCell>
                            </Table.Row>
                        </Table.Header>

                        <Table.Body>

                        {
                            peopleData.map((person, index) => {
                                return (
                                    <Table.Row key={index}>
                                        <Table.Cell singleLine>{ person.first_name }</Table.Cell>
                                        <Table.Cell singleLine>{ person.last_name }</Table.Cell>
                                        <Table.Cell singleLine>{ person.email_address }</Table.Cell>
                                        <Table.Cell singleLine>{ person.status }</Table.Cell>
                                    </Table.Row>
                                );
                            })
                        }

                        </Table.Body>
                    </Table>
                    <Table id="groupsTable" celled padded sortable>
                    <Table.Header>
                        <Table.Row>
                            <Table.HeaderCell sorted={groupColumn === 'id' ? groupDirection : null} onClick={this.handleGroupSort('id')}>Group ID</Table.HeaderCell>
                            <Table.HeaderCell singleLine sorted={groupColumn === 'group_name' ? groupDirection : null} onClick={this.handleGroupSort('group_name')}>Group Name</Table.HeaderCell>
                        </Table.Row>
                    </Table.Header>

                    <Table.Body>

                    {
                        groupData.map((group, index) => {
                            return (
                                <Table.Row key={index}>
                                    <Table.Cell singleLine>{ group.id }</Table.Cell>
                                    <Table.Cell singleLine>{ group.group_name }</Table.Cell>
                                </Table.Row>
                            );
                        })
                    }

                    </Table.Body>
                </Table>
            </div>
    );
}

}

export default ResultsList
