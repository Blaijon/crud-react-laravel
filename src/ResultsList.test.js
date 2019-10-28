import React from 'react';
import { shallow, configure } from 'enzyme';
import ResultsList from './ResultsList';
import Adapter from 'enzyme-adapter-react-16'
import "core-js/stable"
import "regenerator-runtime/runtime"

let wrapper, data;
configure({adapter: new Adapter()});

describe('<ResultsList />', () => {

    beforeAll(() => {
        wrapper = shallow(<ResultsList />)
        data = [{
            "id": 132,
            "first_name": "Macie",
            "last_name": "Emmerich",
            "email_address": "cremin.marjory@hotmail.com",
            "status": "active",
            "updated_at": "2019-07-20 22:05:47",
            "created_at": "2019-07-20 22:05:47"
        }]

        wrapper.setState({ 'peopleData' : data });
    });

    test('should match the snapshot', () => {
        expect(wrapper).toMatchSnapshot();
    });
})
