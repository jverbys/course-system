import React from "react";
import {Container, Nav, Navbar, NavDropdown} from "react-bootstrap";
import {BrowserRouter as Router, Link, Route, Routes} from "react-router-dom";
import Courses from "./components/Courses";
import Course from "./components/Course";

const App = () => {
    return (
        <Router>
            <Navbar fixed="top" bg="dark" variant="dark" expand="lg">
                <Container>
                    <Link to="/" style={{ textDecoration: 'none' }}>
                        <Navbar.Brand>Course system</Navbar.Brand>
                    </Link>
                    <Navbar.Toggle aria-controls="basic-navbar-nav" />
                    <Navbar.Collapse id="basic-navbar-nav">
                        <Nav className="me-auto">
                            <NavDropdown title="Dropdown" id="basic-nav-dropdown">
                                <NavDropdown.Item href="#action/3.1">Action</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.2">Another action</NavDropdown.Item>
                                <NavDropdown.Item href="#action/3.3">Something</NavDropdown.Item>
                                <NavDropdown.Divider />
                                <NavDropdown.Item href="#action/3.4">Separated link</NavDropdown.Item>
                            </NavDropdown>
                        </Nav>
                    </Navbar.Collapse>
                </Container>
            </Navbar>
            <Container style={{marginTop: '80px'}}>
                <Routes>
                    <Route path="/" element={<Courses />} />
                    <Route path="/courses/:courseId" element={<Course />} />
                </Routes>
            </Container>
        </Router>
    )
};

export default App;